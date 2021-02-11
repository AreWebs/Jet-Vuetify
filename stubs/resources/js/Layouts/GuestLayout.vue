<template>
    <v-app>
        <v-app-bar
            app
            color="white"
            flat
            elevate-on-scroll
            >
            <inertia-link href="/" >
                <v-avatar
                class="mr-10"
                color="grey darken-1"
                size="32"
                ></v-avatar>
            </inertia-link>

            <v-spacer></v-spacer>
            <inertia-link v-if="$page.props.user" href="/dashboard" >
                Dashboard
            </inertia-link>

            <inertia-link v-if="!route().current('login') && !route().current('register')" :href="route('login')"
                as="v-btn" text outlined>
                Login
            </inertia-link>
            <inertia-link v-if="!route().current('login') && !route().current('register')"  :href="route('register')"
                as="v-btn" color="primary" class="ml-2">
                Register
            </inertia-link>
            
            <div v-if="route().current('login') || route().current('register')">
                <transition name="slide-fade" mode="out-in">
                    <div key="registerLink" v-if="route().current('login')">Don't have an account? <inertia-link :href="route('register')">Sign up</inertia-link></div>
                    <div key="loginLink" v-else>Already have an account? <inertia-link :href="route('login')">Sign in</inertia-link></div>
                </transition>
            </div>
        </v-app-bar>
        
        <v-main class="mt-6">
            <slot />
        </v-main>


        <v-footer
            padless>
            <v-row justify="center" no-gutters>
                <v-col class="py-4 text-center" cols="12">
                    © AreWebs <!--2021 - --> {{ new Date().getFullYear() }} 
                </v-col>
            </v-row>
        </v-footer>
    </v-app>
</template>

<style>
main.v-main {
    background-color: #f3f4f6;
}
/* Transitions */
.slide-fade-enter-active {
    transition: all .3s ease;
}
.slide-fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
}
</style>