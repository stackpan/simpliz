<div>
    @foreach($options as $option)
        <div class="flex my-2 gap-2" >
            <input type="radio" name="optionId" id="{{ "option-$loop->index" }}"
                   class="mt-1 text-primary-content/70 focus:ring-primary-content/40"
                   wire:key="{{ $option['id'] }}"
                   wire:click="setAnswer('{{ $option['id'] }}')"
                   @if($userOption->option_id === $option['id'])
                       checked
                @endif
            >
            <label for="{{ "option-$loop->index" }}">{{ $option['body'] }}</label>
        </div>
    @endforeach
</div>
