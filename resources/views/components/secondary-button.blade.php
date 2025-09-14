<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400 disabled:text-gray-200 inline-flex items-center justify-center shadow-md rounded-md font-semibold text-xs uppercase tracking-widest text-white focus:ring-4 focus:outline-none focus:ring-blue-300 px-5 py-2.5 text-center bg-primary-500 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-700 dark:focus:ring-blue-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
