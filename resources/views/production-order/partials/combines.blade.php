@foreach ($sales as $sale)
    <div class="py-2 border-t border-primary-100 dark:border-primary-700">
        <div class="flex flex-row items-center">
            <input type="hidden" id="prod_status" value="{{ $sale->prod_order->isactive }}" />
            <input type="checkbox" id="order[{{ $sale->id }}]" name="order[{{ $sale->id }}]" tabindex="8"
                value="{{ $sale->id }}" {{ $sale->prod_order->isactive == '0' ? 'disabled checked' : '' }} />
            <label for="order[{{ $sale->id }}]" class="pl-2">{{ $sale->no_order }}</label>
        </div>
    </div>
@endforeach
