<?php

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;




new class extends Component
{
    #[Modelable]
    public $value = '';

    #[Reactive]
    public $items;

    public $name;
    
    public function mount($items, $name)
    {
        $this->items = $items;
        $this->name = $name;
        
    }
};


?>

  
   <div class="mb-3" wire:ignore>
        <select  id="{{$name}}" class="form-select @error('value') is-invalid  @enderror" 
         wire:model.live="value" >
        <option value="" selected >Select {{$name}}</option>
        @foreach($items as $item)       
        <option value="{{$item->id}}" wire:key="{{ $item->id }}" >{{ $item->name}}</option>
        @endforeach
        </select>
        @error('value')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
   </div>


    @script 
   <script>
          $(function() {
            
            $('#{{$name}}').select2().on('change', function() {
                 $wire.$set('value', $(this).val())
            });


          });

    
   </script>
   @endscript
