<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
//use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component
{


 use WithPagination;


public function deleteUser(int $id)
{
    User::query()->find($id)->delete();
}

// public function validateField($field)
// {
//     $this->validateOnly($field);
// }

#[On('user-created')] 
public function updateUserList($user)
{
  
}

public function render()
{


    return $this->view([
        'users' => User::query()->with('country')->orderBy('id', 'desc')->paginate(20)
    ]);

    
}

}

?>


<div>
     
    <ul>
        @forelse($users as $user)

        <li wire:key="{{$user->id}}" wire:transition>{{ $user->name}} ({{$user->email}}) | {{ $user->country->name}}
            <a href="" 
        wire:click.prevent="deleteUser({{ $user->id}}) " wire:confirm="Вы действительно хотите удалить?" >Delete</a>
        @if($user->avatar)
        <img src="{{ asset('uploads/' . $user->avatar)}}" alt="Preview" height="50" >
        @endif
    
        </li>
        @empty

        <p>List is empty ...</p>
        @endforelse
    </ul>

    {{ $users->links() }}
</div>

    
