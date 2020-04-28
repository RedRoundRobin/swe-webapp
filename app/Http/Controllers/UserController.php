<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Providers\EntityServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @var UserServiceProvider
     */
    private $provider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->provider = new UserServiceProvider();
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $users = $this->provider->findAll();
        $entities = (new EntityServiceProvider())->findAll();
        $nullOrNot = function ($u) use (&$entities) {
            $entity = array_filter($entities, function ($e) use (&$u) {
                return $u->entity == $e->entityId;
            });
            if (empty($entity)) {
                return null;
            }
            return array_pop($entity);
        };

        foreach ($users as $u) {
            $usersWithEntity[] = ['user' => $u, 'entity' => $nullOrNot($u)];
        }
        return view('users.index', compact('usersWithEntity'));
    }

    /**
     * Display the specified resource.
     *
     * @param $user
     * @return Renderable
     */
    public function show($user)
    {
        $user = $this->provider->retrieveById($user);
        $entity = (new EntityServiceProvider())->findFromUser($user);
        return view('users.show', compact(['user', 'entity']));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $entityProvider = new EntityServiceProvider();
        $entities = $entityProvider->findAll();
        return view('users.create', compact('entities'));
    }

    /**
     * @param $user
     * @return Factory|View
     */
    public function edit($user)
    {
        $user = $this->provider->retrieveById($user);
        return view('users.edit', compact('user'));
    }

    /**
     *
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|max:32',
            'surname' => 'required|string|max:32',
            'email' => 'required|email|max:32',
            'entityId' => 'nullable|numeric|required_if:' . Auth::user()->getRole() . ',==,Admin',
            'type' => 'nullable|numeric|required_if:' . Auth::user()->getRole() . ',==,Admin',
        ]);
        $data['password'] = substr(md5(microtime()), rand(0, 26), 6);
        if (!key_exists('entityId', $data)) {
            $data['entityId'] = (new EntityServiceProvider())->findFromUser(Auth::id())->entityId;
        }
        if (!key_exists('type', $data)) {
            $data['type'] = 0;
        }
        if (key_exists('password', $data)) {
            $data['password'] = $data['password'];/*todo sha512*/
        }
        return $this->provider->store($data) ? redirect(route('users.index'))
            ->withErrors(['GoodCreate' => 'Utente creato con successo con password: ' . $data['password']]) :
            redirect(route('users.index'))->withErrors(['NotCreate' => 'Utente non creato']);
    }

    /**
     * @param $user
     * @return RedirectResponse|Redirector
     */
    public function update($user)
    {
        $user = $this->provider->retrieveById($user);
        $data = request()->validate([
            'name' => 'required|string|max:32',
            'surname' => 'required|string|max:32',
            'type' => 'in:0,1,2|numeric|required_if:' . Auth::user()->getRole() . '==, "isAdmin"',
            'email' => 'required|email|max:32',
            'telegramName' => 'nullable|string|required_if:tfa,==,true',
            'tfa' => 'nullable|in:true',
            'deleted' => 'nullable|in:true',
            'password' => 'nullable',
        ]);
        $data = array_diff_assoc($data, $user->getAttributes());

        if (key_exists('deleted', $data)) {
            $data['deleted'] = boolval($data['deleted']);
        } else {
            $data['deleted'] = false;
        }

        if (key_exists('tfa', $data)) {
            $data['tfa'] = boolval($data['tfa']);
        }

        if (key_exists('telegramName', $data)) {
            if ($data['telegramName'] != $user->getTelegramName()) {
                $data['tfa'] = false;
            }
        }

        $change = "";
        if (key_exists('password', $data)) {
            $data['password'] =  substr(md5(microtime()), rand(0, 26), 6);
            $change = ' e con nuova password : ' . $data['password'];
        }
        if (key_exists('password', $data)) {
            $data['password'] = $data['password'];/*todo sha512*/
        }

        return $this->provider->update($user->getAuthIdentifier(), $data) ?
            redirect(route('users.index'))
                ->withErrors(['GoodUpdate' => 'Utente aggiornato con successo' . $change]) :
            redirect(route('users.index'))->withErrors(['NotUpdate' => 'Utente non aggiornato']);
    }

    /**
     * @param $userId
     * @return RedirectResponse|Redirector
     */
    public function destroy($userId)
    {
        return $this->provider->destroy($userId) ?
            redirect(route('users.index'))->withErrors(['GoodDestroy' => 'Utente eliminato con successo']) :
            redirect(route('users.index'))->withErrors(['NotDestroy' => 'Utente non eliminato']);
    }

    /**
     * @param $userId
     * @return RedirectResponse|Redirector
     */
    public function restore($userId)
    {
        $user = $this->provider->retrieveById($userId);
        return $this->provider->update($user->getAuthIdentifier(), '{"deleted":false}') ?
            redirect(route('users.index'))->withErrors(['GoodRestore' => 'Utente ripristinato con successo']) :
            redirect(route('users.index'))->withErrors(['NotRestore' => 'Utente non ripristinato']);
    }

    public function reset($userId)
    {
        $data['password'] =  substr(md5(microtime()), rand(0, 26), 6);
        $change = ' e nuova password : ' . $data['password'];
        if (key_exists('password', $data)) {
            $data['password'] = $data['password'];/*todo sha512*/
        }
        return $this->provider->update($userId, $data) ?
            redirect(route('users.show', ['userId' => $userId]))
                ->withErrors(['GoodUpdate' => 'Password aggiornata con successo' . $change]) :
            redirect(route('users.show', ['userId' => $userId]))
                ->withErrors(['NotUpdate' => 'Password non modificata']);
    }
}
