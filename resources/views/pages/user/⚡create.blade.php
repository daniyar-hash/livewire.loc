<?php

use Livewire\Component;
use App\Livewire\Forms\UserForm;
use App\Models\City;
use App\Models\Country;
use App\Models\Street;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;
    
public UserForm $myform;


public  $countries = [];

public  $cities = [];

public  $streets = [];




public function mount()
{
    $this->countries = Country::all();
 

}


   public function updatedMyFormCountryid()
    {       
       
  
           if($this->myform->country_id){

                $this->cities = City::query()->where('country_id', $this->myform->country_id)->get();

              
                
           } else {

              $this->cities = [];

           }
          
        $this->streets = [];
        $this->myform->city_id = "";
        $this->myform->street_id = "";
           
    }


   public function updatedMyFormCityid()
    {       
  

           if($this->myform->city_id){

                $this->streets = Street::query()->where('city_id', $this->myform->city_id)->get();
                
           } else {
            
              $this->streets = [];

           }

         $this->myform->street_id = "";
                    
    }




public function save()

    {
  

    $user = $this->myform->saveUser();

    $this->dispatch('user-created', $user);

    // $this->redirect('/', navigate:true);

    $this->redirectRoute('home', navigate:true);


    }

#[Layout('layouts.main')]
public function render()
    {
        return $this->view();
    }

}

?>

   <div class="col-md-8 offset-2 ">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form wire:submit="save" class="position-relative">

            <div wire:loading wire:target.except="save" style="position:absolute;width:100%;height:100%;background:rgba(255,255,255,.5);z-index:10;text-align:center;padding-top:20px;"
               >
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

            </div>

            <div class="mb-3" >
                <input type="text" wire:model="myform.name""
                class="form-control  @error('myform.name') is-invalid  @enderror"    placeholder="Name">
                @error('myform.name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <input type="email" wire:model="myform.email"
                class="form-control @error('myform.email') is-invalid  @enderror"     placeholder="Email">
                @error('myform.email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3 ">
                <input type="password" wire:model="myform.password"  class="form-control @error('myform.password') is-invalid  @enderror"   placeholder="Password">
                @error('myform.password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>




        <livewire:select-component  
        :items="$countries"
        wire:model.live="myform.country_id"
        name="country"
        :key="$countries->pluck('id')->join('-')"
       />


       @if(count($cities))

        <livewire:select-component
          :items="$cities"
         wire:model.live="myform.city_id"
        name="city"
        :key="$cities->pluck('id')->join('-')"/>
        @endif


        @if(count($streets))
            <livewire:select-component  :items="$streets"  
            wire:model.live="myform.street_id"
            name="street"
             :key="$streets->pluck('id')->join('-')"/>
        @endif

          
            <div class="mb-3 mt-3 ">
                <input type="file" wire:model="myform.avatar"  class="form-control @error('myform.avatar') is-invalid  @enderror">
                @error('myform.avatar')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div wire:loading wire:target="myform.avatar"><span class="text-success">Uploading...</span></div>

                @if(!$errors->has('myform.avatar') && $myform->avatar)

                <img src="{{ $myform->avatar->temporaryUrl()}}"  class="mt-2" width="100">

                @endif
            </div>
              
        
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <div wire:loading  wire:target="save" class="spinner-grow text-primary">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        
         </form>
   </div>

  