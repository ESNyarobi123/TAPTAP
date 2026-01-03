<x-guest-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Progress Bar -->
        <div class="mb-8 bg-gray-100 h-2 rounded-full overflow-hidden">
            <div id="progress-bar" class="bg-orange-red h-full transition-all duration-500" style="width: 15%"></div>
        </div>

        <div id="chat-container" class="flex flex-col min-h-[400px]">
            <!-- Chat Header -->
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-deep-blue rounded-2xl flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-deep-blue">TAPTAP Assistant</h2>
                    <p class="text-xs text-green-500 font-bold uppercase tracking-widest">Online • Ready to help</p>
                </div>
            </div>

            <form id="registration-form" method="POST" action="{{ route('restaurant.register.store') }}" class="flex flex-col flex-1">
                @csrf

                <!-- Step 1: Restaurant Name -->
                <div class="step step-active" data-step="1">
                    <div class="chat-bubble-left">
                        Habari! Karibu TAPTAP. Ningependa kuanza kwa kujua, jina la restaurant yako ni nani?
                    </div>
                    <div class="mt-4">
                        <x-text-input id="restaurant_name" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="text" name="restaurant_name" :value="old('restaurant_name')" required placeholder="Mfano: TAPTAP Grill" />
                        <x-input-error :messages="$errors->get('restaurant_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 2: Location -->
                <div class="step step-hidden" data-step="2">
                    <div class="chat-bubble-left">
                        Safi sana! Na restaurant yako inapatikana wapi (Location)?
                    </div>
                    <div class="mt-4">
                        <x-text-input id="location" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="text" name="location" :value="old('location')" required placeholder="Mfano: Masaki, Dar es Salaam" />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 3: Phone -->
                <div class="step step-hidden" data-step="3">
                    <div class="chat-bubble-left">
                        Nimekupata. Naomba namba ya simu ya restaurant kwa ajili ya mawasiliano.
                    </div>
                    <div class="mt-4">
                        <x-text-input id="phone" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="text" name="phone" :value="old('phone')" required placeholder="Mfano: 0712 345 678" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 4: Manager Name -->
                <div class="step step-hidden" data-step="4">
                    <div class="chat-bubble-left">
                        Vizuri. Sasa, nani atakuwa Manager wa hii restaurant? (Jina lako kamili)
                    </div>
                    <div class="mt-4">
                        <x-text-input id="manager_name" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="text" name="manager_name" :value="old('manager_name')" required placeholder="Mfano: John Doe" />
                        <x-input-error :messages="$errors->get('manager_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 5: Email -->
                <div class="step step-hidden" data-step="5">
                    <div class="chat-bubble-left">
                        Naomba email yako kwa ajili ya ku-login kwenye system.
                    </div>
                    <div class="mt-4">
                        <x-text-input id="manager_email" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="email" name="manager_email" :value="old('manager_email')" required placeholder="Mfano: manager@taptap.com" />
                        <x-input-error :messages="$errors->get('manager_email')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 6: Password -->
                <div class="step step-hidden" data-step="6">
                    <div class="chat-bubble-left">
                        Mwisho kabisa, weka password imara kwa ajili ya usalama wa account yako.
                    </div>
                    <div class="mt-4 space-y-4">
                        <x-text-input id="manager_password" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="password" name="manager_password" required placeholder="Password" />
                        <x-text-input id="manager_password_confirmation" class="block w-full text-lg p-4 rounded-2xl border-2 border-gray-100 focus:border-orange-red" type="password" name="manager_password_confirmation" required placeholder="Confirm Password" />
                        <x-input-error :messages="$errors->get('manager_password')" class="mt-2" />
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-12 flex items-center justify-between">
                    <button type="button" id="prev-btn" class="hidden text-gray-400 font-bold hover:text-deep-blue transition-colors">
                        ← Rudi Nyuma
                    </button>
                    
                    <button type="button" id="next-btn" class="bg-orange-red text-white px-10 py-4 rounded-2xl font-black text-lg shadow-xl shadow-orange-red/30 hover:bg-deep-blue transition-all duration-300">
                        Endelea →
                    </button>

                    <button type="submit" id="submit-btn" class="hidden bg-deep-blue text-white px-10 py-4 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/30 hover:bg-orange-red transition-all duration-300">
                        Kamilisha Usajili ✨
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const nextBtn = document.getElementById('next-btn');
            const prevBtn = document.getElementById('prev-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressBar = document.getElementById('progress-bar');
            let currentStep = 1;

            function updateUI() {
                steps.forEach(step => {
                    if (parseInt(step.dataset.step) === currentStep) {
                        step.classList.remove('step-hidden');
                        step.classList.add('step-active');
                    } else {
                        step.classList.add('step-hidden');
                        step.classList.remove('step-active');
                    }
                });

                // Update Progress Bar
                const progress = (currentStep / steps.length) * 100;
                progressBar.style.width = `${progress}%`;

                // Update Buttons
                if (currentStep === 1) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }

                if (currentStep === steps.length) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }

            nextBtn.addEventListener('click', () => {
                const currentInput = steps[currentStep - 1].querySelector('input');
                if (currentInput && currentInput.value.trim() === '') {
                    currentInput.classList.add('border-red-500');
                    return;
                }
                currentInput.classList.remove('border-red-500');
                
                if (currentStep < steps.length) {
                    currentStep++;
                    updateUI();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateUI();
                }
            });

            // Allow "Enter" key to go to next step
            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && currentStep < steps.length) {
                    e.preventDefault();
                    nextBtn.click();
                }
            });
        });
    </script>
</x-guest-layout>
