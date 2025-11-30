@foreach ($sales as $sale)
    @if ($sale->prod_order)
        @php
            $a = App\Models\SaleOrderMitra::where('sale_order_id', $sale->id)->count();
        @endphp
        @if ($a > 0)
            <div class="py-2 border-t border-primary-100 dark:border-primary-700">
                <div id="list-table" class="flex flex-row items-center">
                    <input type="hidden" id="prod_status" value="{{ $sale->prod_order->isactive }}" />
                    <input type="checkbox" id="order[{{ $sale->id }}]" name="order[{{ $sale->id }}]" tabindex="8"
                        value="{{ $sale->id }}"
                        class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-5 h-5 rounded-lg shadow-md"
                        {{ $sale->prod_order->isactive == '0' ? 'disabled checked' : '' }} />
                    <label for="order[{{ $sale->id }}]" class="pl-2">{{ $sale->no_order }}</label>
                </div>
            </div>
        @endif
    @endif
@endforeach
