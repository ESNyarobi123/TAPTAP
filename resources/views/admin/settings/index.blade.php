<x-admin-layout>
    <x-slot name="header">
        System Settings
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- General Settings -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">General Configuration</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Basic system parameters and branding</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">System Name</label>
                        <input type="text" name="system_name" value="{{ $settings['general']['system_name']->value ?? 'TAPTAP' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Support Email</label>
                        <input type="email" name="support_email" value="{{ $settings['general']['support_email']->value ?? 'support@taptap.com' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                    </div>
                </div>
            </div>

            <!-- Financial Settings -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Financial Parameters</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Commission rates and withdrawal limits</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">System Commission (%)</label>
                        <input type="number" name="commission_rate" value="{{ $settings['financial']['commission_rate']->value ?? '5' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Min. Withdrawal (Tsh)</label>
                        <input type="number" name="min_withdrawal" value="{{ $settings['financial']['min_withdrawal']->value ?? '50000' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                    </div>
                </div>
            </div>

            <!-- Bot & API Settings -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">System Automation</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Global bot endpoints and API settings</p>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Global Webhook Secret</label>
                        <input type="text" name="webhook_secret" value="{{ $settings['api']['webhook_secret']->value ?? '' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-mono focus:ring-2 focus:ring-slate-900 transition-all" placeholder="sk_live_xxxxxxxxxxxx">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                <button type="reset" class="px-8 py-4 bg-slate-50 text-slate-400 rounded-2xl font-bold text-sm hover:bg-slate-100 transition-all">Reset Changes</button>
                <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-2xl hover:shadow-slate-900/20 transition-all">Save All Settings</button>
            </div>
        </form>
    </div>
</x-admin-layout>
