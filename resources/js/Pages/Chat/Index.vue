﻿<script>
import {Link} from '@inertiajs/vue3';
import Main from "@/Layouts/Main.vue";
export default {
    name: "Index",

    props: [
        'users',
        'chats',
    ],

    components: {
        Link
    },

    data() {
        return {
            isGroup: false,
            userIds: [],
            title: null,
        }
    },

    layout: Main,

    methods: {
        store(id) {
            this.$inertia.post('/chats', {title: null, users: [id]})
        },

        storeGroup(){
            if (this.userIds.length < 2) return
            this.$inertia.post('/chats', {title: this.title, users: this.userIds})
        },

        toggleUsers(id){
            let index = this.userIds.indexOf(id)
            if (index === -1) {
                this.userIds.push(id)
            } else {
                this.userIds.splice(index, 1)
            }
            console.log(this.userIds);
        },

        refreshUserIds(){
            this.userIds = []
            this.isGroup = false
        },
    }
}
</script>

<template>
  <div class="flex items-start">
      <div class="w-1/2 p-4 mr-4 bg-white border border-gray-200" >
          <h3 class="text-gray-700 mb-4 text-lg">Chats</h3>
          <div v-if="chats">
              <div v-for="chat in chats" class="flex items-center pb-4 mb-4 border-b border-gray-300">
                  <Link :href="route('chats.show', chat.id)" class="flex">
                      <p>{{chat.title ?? 'Chat Title'}}</p>
                  </Link>
              </div>
          </div>
      </div>
      <div class="w-1/2 p-4 bg-white border border-gray-200">
          <div class="flex items-center mb-4 justify-between">
            <h3 class="text-gray-700 text-lg">Users</h3>
            <a v-if="!isGroup" @click.prevent="isGroup = !isGroup" href="#" class=" inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg">Make Group</a>
            <div v-if="isGroup" class="flex items-center">
                <input class="mr-4 h-8 border border-gray-300 rounded-full" type="text" placeholder="Title" v-model="title">
                <a href="#" @click.prevent="storeGroup"
                   :class="['inline-block mr-2 bg-green-600 text-white text-xs px-3 py-2 rounded-lg',
                   this.userIds.length > 1 ? 'bg-green-600' : 'bg-green-200']">Submit</a>
                <a href="#" @click.prevent="refreshUserIds"
                   class=" inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg">Cancel</a>
            </div>
          </div>
          <div v-if="users">
              <div v-for="user in users" class="flex justify-between items-center pb-4 mb-4 border-b border-gray-300">
                  <div class="flex items-center">
                      <p class="mr-4">{{user.name}}</p>
                      <a v-if="!isGroup" @click.prevent="store(user.id)" href="#"
                         class="inline-block bg-sky-400 text-white text-xs px-3 py-2 rounded-lg">Message</a>
                  </div>
                  <div v-if="isGroup">
                      <input @click="toggleUsers(user.id)" type="checkbox">
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<style scoped>

</style>
