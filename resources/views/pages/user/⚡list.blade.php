<?php

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
//use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component
{
     use WithPagination;

     #[Url]
     public int $limit = 10;

     public array $limitList = [10,25,50,100];
  
     #[Url]
     public string $search = '';

     public string $fieldNameOrder = 'users.id';

     public string $sortOrder = 'desc';

     public array $fieldOrderList = [

        'users.id' => 'Id',  
        'users.name' => 'Name',  
        'users.email' => 'Email',  
        'countries.name' => 'Country',  
     ];

    public function mount()
    {
        if(!in_array($this->limit, $this->limitList))
            {
                $this->redirectRoute('home');
            }
    }


    public function setOrderField(string $field)
    {

        if($this->fieldNameOrder == $field){
            $this->sortOrder = $this->sortOrder =='desc' ?  'asc' : 'desc';
            return;
        }

        $this->fieldNameOrder = $this->fieldOrderList[$field] ? $field : 'users.id';

        $this->sortOrder = 'asc';



    }


     public function updating(string $property, $value)
    {

        if($property =='search'){
            $this->resetPage();
        }

    }


    public function changeLimit()
    {
       $this->limit = in_array($this->limit, $this->limitList) ? $this->limit : $this->limitList[0];
       $this->resetPage();

    }

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


        $users = User::query()
            //  ->with('country')
            ->select('users.id','users.name', 'users.email', 'countries.name as country_name')
            ->join('countries', 'users.country_id', '=' , 'countries.id')
            ->when($this->search, function ($query, $search) {
                 $query->whereLike('users.name', '%' . $search . '%')
                 ->orWhereLike('users.email', '%' . $search . '%')
                ->orWhereLike('countries.name', '%' . $search . '%');
            })
            ->orderBy($this->fieldNameOrder, $this->sortOrder)
            ->paginate($this->limit);

       
        return $this->view([
            'users' => $users
        ]);
        
    }
}
?>

<div class="col-12">


    <div class="d-flex justify-content-between mb-2" id="list">

    <div>
        {{-- <select class="form-select" wire:model.live="limit">
            @foreach($limitList as $key=>$val)
            <option @if($val== $this->limit)selected @endif wire:key="{{ $key}}" value="{{$val}}">{{$val }}</option>
            @endforeach
        </select> --}}


        <select class="form-select" wire:model="limit" wire:change="changeLimit">
            @foreach($limitList as $key=>$val)
            <option @if($val== $this->limit) selected @endif wire:key="{{ $key}}" value="{{$val}}">{{$val}}</option>
            @endforeach
        </select>
    </div>
    
    <div>

    <input type="search" class="form-control" wire:model.live.debounce.300ms="search"  placeholder="Search...">

    </div>



    </div>

  <div class="table-responsive position-relative">

  <div wire:loading style="position:absolute;width:100%;height:100%;
  background:rgba(255,255,255,.7)" class="text-center pt-2">
     <div class="spinner-border" role="status" >
        <span class="visually-hidden">Loading...</span>
    </div>
  </div>
   

  <table class="table table-striped table-hover">

  <thead>
    <tr>
        <th wire:click="setOrderField('users.id')" style="cursor: pointer;">
             <x-sort-field fieldName="Id" :fieldNameOrder="$fieldNameOrder" 
        :sortOrder="$sortOrder" :fieldOrderList="$fieldOrderList" />
        </th>

        <th wire:click="setOrderField('users.name')" style="cursor: pointer;">
        <x-sort-field fieldName="Name" :fieldNameOrder="$fieldNameOrder" 
        :sortOrder="$sortOrder" :fieldOrderList="$fieldOrderList" />
        </th>

        <th wire:click="setOrderField('users.email')" style="cursor: pointer;">
             <x-sort-field fieldName="Email" :fieldNameOrder="$fieldNameOrder" 
        :sortOrder="$sortOrder" :fieldOrderList="$fieldOrderList" />
        </th>
        <th wire:click="setOrderField('countries.name')" style="cursor: pointer;">
             <x-sort-field fieldName="Country" :fieldNameOrder="$fieldNameOrder" 
        :sortOrder="$sortOrder" :fieldOrderList="$fieldOrderList" />
        </th>
        
        <th>Delete</th>
    </tr>
     
  </thead>
  <tbody>
    @foreach($users as $user)
    <tr wire:model="{{$user->id}}">
        <td>{{$user->id}}</td>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->country_name}}</td>
        <td><button type="button" class="btn btn-danger" wire:click="deleteUser({{ $user->id}})" 
         wire:confirm="Вы действительно хотите удалить?">
            Delete
        </button>


        </td>
    </tr>
    @endforeach
  </tbody>

  </table>

  </div>

    {{ $users->links(data:['scrollTo'=> '#list']) }}
</div>