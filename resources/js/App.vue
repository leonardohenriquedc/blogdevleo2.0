<template>
    <div class="flex min-h-screen flex-col bg-white">
        <Header />
        <RouterView class="flex-1" />
        <Footer />
    </div>
</template>

<script setup lang="js">
import Footer from './components/Footer.vue';
import Header from './components/Header.vue';
</script>
