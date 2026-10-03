<?php

use App\Livewire\Forms\UserForm;
use App\Models\Country;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

new class extends Component
{


use WithFileUploads;
    
public UserForm $myform;

public function save()

{

$user = $this->myform->saveUser();

$this->dispatch('user-created', $user);


}

// #[Computed]
// public function countries()
// {
//     return Country::all();
// }

// public function rendering($view)
// {
//     // Передаем переменную $countries в Blade-шаблон
//     $view->with([
//         'countries' => Country::all() // Либо массив: ['RU' => 'Россия', 'KZ' => 'Казахстан']
//     ]);
// }

public function render()
{
    return $this->view([
        'countries' => Country::all()
    ]);
}


}
?>

   <div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form wire:submit="save" >
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
            <div class="mb-5 ">

            <div wire:ignore>
                  <select class="form-select select2  @error('myform.country_id') is-invalid  @enderror" wire:model="myform.country_id" >
                    <option selected>Select Country</option>
                    @foreach($countries as $country)       
                        <option value="{{$country->id}}">{{ $country->name}}</option>
                    @endforeach
                    
                </select>
            </div>

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
              
                @error('myform.country_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <div wire:loading  wire:target="save" class="spinner-grow text-primary">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        
         </form>
   </div>

   @script 
   <script>
    // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function() {

            let select2 = $('.select2');

                select2.select2();

                select2.on('change', function(){

            //   console.log($(this).val())

                $wire.myform.country_id = $(this).val();
                // $wire.set('myform.country_id', $(this).val(), false)

                 console.log($wire.myform.country_id)

                })

                $wire.on('user-created', ()=>{
                    select2.val('Select Country').trigger('change')
                })


            });
   </script>
   @endscript




