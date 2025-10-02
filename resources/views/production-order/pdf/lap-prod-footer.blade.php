<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $text = __("Page :pageNum/:pageCount | Copyright {{ date('Y') . ', ' . config('custom.company_name') }}", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
            $font = null;
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;

            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

            {{-- $x = ($pdf->get_width() - $textWidth) / 2; --}}
            $x = ($pdf->get_width() - $textWidth - 20);
            $y = $pdf->get_height() - 20;

            $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        ');
    }
</script>
