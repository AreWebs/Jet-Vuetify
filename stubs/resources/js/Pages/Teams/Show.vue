<template>
    <app-layout>
        <template #header>
            Team Settings
        </template>

        <div>
            <div class="mx-auto py-10 px-sm-6 px-lg-8">
                <update-team-name-form :team="team" :permissions="permissions" />

                <team-member-manager class="mt-10 sm:mt-0"
                            :team="team"
                            :available-roles="availableRoles"
                            :user-permissions="permissions" />

                <template v-if="permissions.canDeleteTeam && ! team.personal_team">
                    <v-divider class="my-8" />

                    <delete-team-form class="mt-10 sm:mt-0" :team="team" />
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout';
    import TeamMemberManager from './TeamMemberManager'
    import DeleteTeamForm from './DeleteTeamForm'
    import UpdateTeamNameForm from './UpdateTeamNameForm'

    export default {
        props: [
            'team',
            'availableRoles',
            'permissions',
        ],

        components: {
            AppLayout,
            DeleteTeamForm,
            TeamMemberManager,
            UpdateTeamNameForm,
        },
    }
</script>
