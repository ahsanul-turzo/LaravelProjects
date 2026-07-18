<label class="relative inline-flex cursor-pointer items-center">
    <input type="checkbox" id="theme-toggle-checkbox" class="peer sr-only" />
    <div class="peer h-8 w-15 rounded-full bg-gray-300 after:absolute after:left-0.5 after:top-0.5 after:h-7 after:w-7 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-green-500 peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
</label>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkbox = document.getElementById('theme-toggle-checkbox');

        // Sync the toggle state visually on page load
        if (document.documentElement.classList.contains('dark')) {
            checkbox.checked = true;
        }

        // Handle click events
        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        });
    });
</script>
