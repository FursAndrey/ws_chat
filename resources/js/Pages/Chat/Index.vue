<template>
    <div class="flex">
        <div class="w-3/4 p-4 mr-2 border border-sky-800 bg-white rounded-lg h-fit">
            <h3 class="font-semibold text-lg mb-6 mx-auto w-20">
                Chat list.
            </h3>
            <div v-if="chats">
                <div v-for="chat in chats" :key="chat.id" class="mb-2 pb-2 border-b border-gray-300 flex justify-between">
                    <Link :href="route('chats.show', chat.id)" class="flex justify-between w-full">
                        <div>
                            <span>
                                {{ chat.id }}
                            </span>
                            <span class="ml-4">
                                {{ chat.title ?? 'Your chat' }}
                            </span>
                        </div>
                        <div v-if="chat.unreadable_messages_count > 0" class="px-3 py-1 bg-amber-300 rounded-full text-white">
                            {{ chat.unreadable_messages_count }}
                        </div>
                    </Link>
                </div>
            </div>
        </div>
        <div class="w-1/4 p-4 ml-2 border border-sky-800 bg-white rounded-lg h-fit">
            <div class="mb-6 mx-auto flex items-center justify-between">
                <h3 class="font-semibold text-xl w-20 mr-4">
                    User list.
                </h3>
                <div v-if="is_group">
                    <input type="text" placeholder="Group name" v-model="title" class="mr-4 rounded-lg w-40">
                    <span @click="create_group" :class="['px-3 py-2 text-white rounded-lg mr-4', this.user_ids.length < 2 ? 'bg-gray-400' : 'bg-green-600 cursor-pointer']">
                        Go to chat
                    </span>
                    <span @click="cancel" class="px-3 py-2 bg-amber-600 text-white rounded-lg cursor-pointer">
                        X
                    </span>
                </div>
                <span v-if="!is_group" @click="show_checkbox" class="px-3 py-2 bg-indigo-600 text-white rounded-lg cursor-pointer">
                    Make group
                </span>
            </div>
            <div v-if="users">
                <div v-for="user in users" :key="user.id" class="mb-2 pb-2 border-b border-gray-300 flex items-center justify-between">
                    <div class="flex items-center justify-between w-3/5">
                        <span>
                            {{ user.name }}
                        </span>
                        <span @click="store(user.id)" class="px-3 py-2 bg-sky-600 text-white rounded-lg cursor-pointer">
                            Create chat
                        </span>
                    </div>
                    <div v-if="is_group" class="mr-8">
                        <input type="checkbox" @click="toggleUsers(user.id)">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import My from '@/Layouts/My.vue';
export default {
    name: 'Index',
    layout: My,
    props: [
        'users',
        'chats',
    ],
    data() {
        return {
            is_group: false,
            user_ids: [],
            title: null,
        }
    },
    components: {
        Link
    },
    methods: {
        store(targetUserId) {
            this.$inertia.post('/chats', { title: null, users: [targetUserId] });
        },

        show_checkbox() {
            this.is_group = true;
        },

        cancel() {
            this.is_group = false;
            this.user_ids = [];
        },

        create_group() {
            if (this.user_ids.length < 2) {
                return false;
            }

            this.$inertia.post('/chats', { title: this.title, users: this.user_ids });
        },

        toggleUsers(id) {
            let index = this.user_ids.indexOf(id);
            if (index === -1) {
                this.user_ids.push(id);
            } else {
                this.user_ids.splice(index, 1);
            }
        }
    }
}
</script>

<style>

</style>