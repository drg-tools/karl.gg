@if($gun['id'] == 12 || $gun['id'] == 4 || $gun['id'] == 15 || $gun['id'] == 6)
    <div class="font-sans bg-orange-100 border-t-4 border-blue-500 rounded-b text-black px-4 py-3 shadow-md mb-2" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Heads up -- There's more in-depth analysis available for this weapon</p>
                <p class="text-sm">This weapon has been simulated in multiple ways by MeatShield in his <a href="https://github.com/drg-tools/drg-weapons-calculator" target="_blank">DRG Weapons Calculator</a>. Please use that tool if you want to simulate the full iterations of this gun.</p>
            </div>
        </div>
    </div>
@endif
