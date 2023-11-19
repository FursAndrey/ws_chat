<template>
    <div class="flex">
        <div class="w-3/4 p-4 mr-2 border border-sky-800 bg-white rounded-lg">
            <h3 class="font-semibold text-lg mb-6 mx-auto w-1/2 text-center">
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
            <div v-if="messages">
                <div v-for="message in messages" :key="message.id" :class="['m-3 p-3 w-max rounded-xl border', message.is_owner ? 'bg-sky-50 border-sky-400' : 'bg-green-50 border-green-400 ml-auto']">
                    <div class="text-xs italic mb-2 text-left w-max ml-auto">
                        <div>{{ message.user_name }}</div>
                        <div>{{ message.time }}</div>
                    </div>
                    <div>{{ message.body }}</div>
                </div>
                <div class="mx-auto px-3 py-2 bg-blue-500 text-white rounded-lg cursor-pointer text-center w-max" @click="getMessages()" v-if="this.page < lastPage">
                    load
                </div>
            </div>
        </div>
        <div class="w-1/4 p-4 ml-2 border border-sky-800 bg-white rounded-lg h-fit">
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
        'messages',
        'lastPage',
    ],
    data() {
        return {
            body: '',
            page: 1,
        }
    },
    created() {
        window.Echo.private('store-message-channel-' + this.chat.id)
        .listen('.store-message', res => {
            this.messages.unshift(res.message);

            if (this.$page.url === '/chats/' + this.chat.id) {   
                axios.put('/messageStatus', { user_id: this.$page.props.auth.user.id, message_id: res.message.id })
            }
        })
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
                this.body = '';
                this.messages.unshift(res.data);
            });
        },

        getMessages() {
            axios.get(`/chats/${this.chat.id}?page=${++this.page}`)
                .then(res => {
                    this.$page.props.lastPage = res.data.lastPage;
                    this.messages.push(...res.data.messages);
                });
        }
    }
}
</script>

<style>

</style>