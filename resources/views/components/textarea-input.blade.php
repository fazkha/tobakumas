@props([
    'disabled' => false,
    'required' => false,
])

<textarea @disabled($disabled) @required($required)
    {{ $attributes->merge(['class' => 'w-full text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 !border-primary-100 !bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300']) }}>{{ $slot }}</textarea>
