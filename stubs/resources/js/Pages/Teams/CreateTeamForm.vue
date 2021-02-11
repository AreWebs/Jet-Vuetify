<template>
    <app-form-section @submitted="createTeam">
        <template #title>
            Team Details
        </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #form>
            <div class="col-span-6">
                <div>Team Owner</div>

                <div class="d-flex items-center my-2">
                    <v-avatar>
                        <img :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                    </v-avatar>

                    <div class="ml-4 leading-tight">
                        <div>{{ $page.props.user.name }}</div>
                        <div class="text-gray-700 text-sm">{{ $page.props.user.email }}</div>
                    </div>
                </div>
            </div>

            <v-text-field
                class="mt-1"
                label="Team Name"
                type="text"
                v-model="form.name" autofocus 
                outlined
                ></v-text-field>

            <!-- <jet-input-error :message="form.errors.name" class="mt-2" /> -->
        </template>

        <template #actions>
            <v-btn color="secondary" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </v-btn>
        </template>
    </app-form-section>
</template>

<script>
    import AppFormSection from '@/Components/FormSection'

    export default {
        components: {
            AppFormSection,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                })
            }
        },

        methods: {
            createTeam() {
                this.form.post(route('teams.store'), {
                    errorBag: 'createTeam',
                    preserveScroll: true
                });
            },
        },
    }
</script>
