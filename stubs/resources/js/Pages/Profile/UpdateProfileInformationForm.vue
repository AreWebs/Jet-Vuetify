<template>
    <app-form-section @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="mb-4" v-if="$page.props.jetstream.managesProfilePhotos">
                <!-- Profile Photo File Input -->
                <input type="file" style="display: none;"
                    ref="photo"
                    @change="updatePhotoPreview">

                <!-- New Profile Photo Preview -->
                <v-avatar size="80">
                    <img :src="photoPreview" v-if="photoPreview">
                    <img :src="user.profile_photo_url" :alt="user.name" v-else>

                </v-avatar>

                <v-btn class="ml-3 mt-2 mr-2" outlined color="info" @click.native.prevent="selectNewPhoto">
                    Select A New Photo
                </v-btn>
                <v-btn class="mt-2" outlined color="info" @click.native.prevent="deletePhoto" v-if="user.profile_photo_path">
                    Remove Photo
                </v-btn>

                <!-- <jet-input-error :message="form.errors.photo" class="mt-2" /> -->
            </div>

            <!-- Name -->
            <v-text-field
                label="Name"
                type="text"
                outlined autocomplete="name"
                 v-model="form.name"
                :error-messages="form.errors.name"></v-text-field>

            <!-- Email -->
            <v-text-field
                label="Email"
                type="email" 
                outlined
                v-model="form.email"
                :error-messages="form.errors.email"></v-text-field>
        </template>

        <template #actions>
            <transition 
                
                leave-active-class="transition ease-in duration-1000" 
                leave-class="opacity-100" 
                leave-to-class="opacity-0">
                <div v-show="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </div>
            </transition>

            <v-btn class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" color="secondary">
                Save
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

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    name: this.user.name,
                    email: this.user.email,
                    photo: null,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => (this.photoPreview = null),
                });
            },
        },
    }
</script>
