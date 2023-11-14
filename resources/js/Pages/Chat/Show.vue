<template>
    <div class="flex">
        <div class="w-3/4 p-4 mr-2 border border-sky-800 bg-white rounded-lg">
            {{ chat }}
        </div>
        <div class="w-1/4 p-4 ml-2 border border-sky-800 bg-white rounded-lg">
            <h3 class="font-semibold text-lg mb-6 mx-auto w-20">
                User list.
            </h3>
            <div v-if="users">
                <div v-for="user in users" :key="user.id" class="mb-2 pb-2 border-b border-gray-300 flex items-center justify-between">
                    <span>
                        {{ user.name }}
                    </span>
                    <span @click="store(user.id)" class="px-3 py-2 bg-sky-600 text-white rounded-lg cursor-pointer">
                        Create chat
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import My from '@/Layouts/My.vue';
export default {
    name: 'Show',
    layout: My,
    props: [
        'users',
        'chat',
    ],
    methods: {
        
    }
}
</script>

<style>

</style>