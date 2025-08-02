@props([
    'disabled' => false,
    'required' => false,
])

<input @disabled($disabled) @required($required)
    {{ $attributes->merge(['class' => 'w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border bg-primary-20 border-primary-100 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300']) }} />
