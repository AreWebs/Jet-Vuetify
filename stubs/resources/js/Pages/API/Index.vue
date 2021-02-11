<template>
    <app-layout>
        <template #header>
            API Tokens
        </template>

        <div>
            <div class="mx-auto py-10 px-sm-6 px-lg-8">
                <api-token-manager :tokens="tokens"
                                   :available-permissions="availablePermissions"
                                   :default-permissions="defaultPermissions" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import ApiTokenManager from './ApiTokenManager'
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        components: {
            ApiTokenManager,
            AppLayout,
        },
    }
</script>
