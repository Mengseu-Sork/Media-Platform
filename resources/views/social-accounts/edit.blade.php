@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-4rem)] bg-[#f5f8f6]">

    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-full px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-blue-700">
                        Media Accounts / Edit
                    </p>

                    <h1 class="mt-2 text-2xl font-black text-blue-700">
                        {{ $socialAccount->platformName }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Manage the connection status. OAuth credentials are not edited manually.
                    </p>
                </div>

                <a
                    href="{{ route('social-accounts.index') }}"
                    class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-700 hover:text-blue-700"
                >
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-full px-6 py-7">

        <form
            method="POST"
            action="{{ route('social-accounts.update', $socialAccount) }}"
            class="rounded-2xl border border-gray-200 bg-white shadow-sm"
        >
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-2 gap-6 p-6">

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        Platform
                    </p>

                    <p class="mt-2 text-sm font-extrabold text-gray-900">
                        {{ $socialAccount->platformName }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        Account
                    </p>

                    <p class="mt-2 text-sm font-extrabold text-gray-900">
                        {{ $socialAccount->display_name ?: ($socialAccount->username ?: $socialAccount->account_id) }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        Account ID
                    </p>

                    <p class="mt-2 break-all text-xs text-gray-600">
                        {{ $socialAccount->account_id }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        Token
                    </p>

                    <p class="mt-2 text-xs text-gray-600">
                        {{ $socialAccount->token_expires_at ? 'Expires '.$socialAccount->token_expires_at->format('d M Y H:i') : 'No expiry recorded' }}
                    </p>
                </div>

            </div>

            <div class="border-t border-gray-100 p-6">

                <label class="flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 p-4">

                    <div>
                        <p class="text-xs font-extrabold text-gray-800">
                            Active for publishing
                        </p>

                        <p class="mt-1 text-[10px] text-gray-400">
                            Disable this connection without deleting its credentials.
                        </p>
                    </div>

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        {{ $socialAccount->is_active ? 'checked' : '' }}
                    >

                </label>

            </div>

            <div class="flex justify-end gap-2 border-t border-gray-100 p-4">

                <a
                    href="{{ route('social-accounts.index') }}"
                    class="rounded-xl border border-gray-200 px-5 py-2.5 text-xs font-bold text-white bg-amber-300 hover:bg-amber-500"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-700 px-6 py-2.5 text-xs font-bold text-white hover:bg-blue-800"
                >
                    Save Changes
                </button>

            </div>
        </form>
    </div>
</div>
@endsection
