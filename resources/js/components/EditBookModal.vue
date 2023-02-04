<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import axios from "axios";

const props = defineProps({ activeBook: Object})
const emit = defineEmits(['selected'])
const activeBook = props.activeBook;
const open = ref(true)
const errorBag = ref('')

const updateBook = () => {
    axios.put(`/api/books/${activeBook.id}`, activeBook).then(() => {
        emit('selected')
    }).catch(error => {
        errorBag.value = error.response.data.message
    })
}

</script>

<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10" @close="open = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                            <div class="mx-2">
                                Edit Book: <strong>{{ activeBook.title }}</strong>
                            </div>
                            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                                    <form class="space-y-6" @submit.prevent="updateBook">
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Title</label>
                                            <div class="mt-1">
                                                <input id="title" v-model="activeBook.title" name="text" type="text" required class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" />
                                                <p class="mt-2 text-sm text-red-600" id="email-error" v-if="errorBag">{{  errorBag }}</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                            <div class="mt-1">
                                                <input id="price" v-model="activeBook.price" name="price" type="text" autocomplete="email" required class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" />
                                            </div>
                                        </div>

                                        <div>
                                            <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update</button>
                                        </div>
                                        <div>
                                            <button type="button" class="flex w-full justify-center rounded-md border border-indigo-600 bg-white py-2 px-4 text-sm font-medium text-indigo-600 shadow-sm" @click="emit('close')">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
export default {
    name: "EditBookModal"
}
</script>

<style scoped>

</style>
