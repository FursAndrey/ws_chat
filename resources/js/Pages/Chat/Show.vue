<template>
    <div class="flex">
        <div class="w-3/4 p-4 mr-2 border border-sky-800 bg-white rounded-lg">
            {{ chat }}
            <h3 class="font-semibold text-lg mb-6 mx-auto w-20">
                {{ chat.title ?? 'Your chat' }}
            </h3>
            <div class="mb-6">
                <h3 class="font-semibold text-lg mb-2">
                    Send messages
                </h3>
                <div>
                    <input type="text" v-model="body" class="w-3/4 rounded-lg">
                    <span @click="store()" class="ml-4 px-3 py-2 bg-sky-600 text-white rounded-lg cursor-pointer">Send</span>
                </div>
            </div>
            <div>

            </div>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import My from '@/Layouts/My.vue';
import axios from 'axios';
export default {
    name: 'Show',
    layout: My,
    props: [
        'users',
        'chat',
    ],
    data() {
        return {
            body: '',
        }
    },
    computed: {
        userIds() {
            return this.users.map(
                user => {
                    return user.id;
                }
            ).filter(
                userId => {
                    return userId !== this.$page.props.auth.user.id;
                }
            )
        }
    },
    methods: {
        store() {
            axios.post(
                '/messages', 
                {
                    body: this.body, 
                    chat_id: this.chat.id,
                    user_ids: this.userIds
                }
            ).then(res => {
                console.log(res);
            });
        }
    }
}
</script>

<style>

</style>