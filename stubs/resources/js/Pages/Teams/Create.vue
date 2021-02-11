<template>
    <app-layout>
        <template #header>
            Create Team
        </template>

        <div>
            <div class="mx-auto py-10 px-sm-6 px-lg-8">
                <create-team-form />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateTeamForm from './CreateTeamForm'

    export default {
        components: {
            AppLayout,
            CreateTeamForm,
        },
    }
</script>
