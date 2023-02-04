<script setup>
import { ref, onMounted } from 'vue'
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import {
    Bars3BottomLeftIcon,
    HomeIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import axios from "axios";
import { TailwindPagination } from 'laravel-vue-pagination';
import EditBookModal from "../components/EditBookModal.vue";
import NotFound from "../components/NotFound.vue";
import router from "../router";

const books = ref([])
const mobileMenuOpen = ref(false)
const loadEditModal = ref(false);
const initials = ref('')
const selectedBook = ref([])
const booksData = ref([]);

const sidebarNavigation = [
    { name: 'Dashboard', href: '#', icon: HomeIcon, current: false }
]
const userNavigation = [
    { name: 'Sign out', href: '/api/logout' },
]

//Auth
const authUserName = authUser?.name

const getInitials = (authUserName) => {
    let words = authUserName.split(" ");
    let firstWord = words[0];
    initials.value = firstWord.substring(0, 2).toUpperCase();
}

//Get Action
const getBooks = async (page = 1) => {
    let response = await axios.get(`/api/books?page=${page}`)
    books.value = response.data.data
    booksData.value = response.data
}

onMounted(() => {
    if (!authUser) {
        return router.push('/login')
    }
    router.replace('/dashboard')
    getBooks()
    getInitials(authUserName)
})

//Edit Action
const toggleEditModal = () => {
    loadEditModal.value = ! loadEditModal.value
}
const editModal = (book) => {
    toggleEditModal()
    selectedBook.value = book
}
const editItem = () => {
    alert('Success')
    toggleEditModal()
    getBooks(booksData.value.meta.current_page)
}


//Delete Action
const deleteBookPrompt = (bookItem) => {
    let response = prompt(`Delete ${bookItem.title}? Type yes to proceed`)
    response === 'yes' ? deleteItem(bookItem.id) : alert('Not Deleted')
}
const deleteItem = (id) => {
    axios.delete(`/api/books/${id}`).then(response => {
        alert('Success')
        getBooks(booksData.value.meta.current_page)
    })
}

</script>
<template>
    <div class="flex h-full">
        <!-- Narrow sidebar -->
        <div class="hidden w-28 overflow-y-auto bg-gray-700 md:block h-screen">
            <div class="flex w-full flex-col items-center py-6">
                <div class="mt-6 w-full flex-1 space-y-1 px-2">
                    <a v-for="item in sidebarNavigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:text-white', 'group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium']" :aria-current="item.current ? 'page' : undefined">
                        <component :is="item.icon" :class="[item.current ? 'text-white' : 'text-indigo-300 group-hover:text-white', 'h-6 w-6']" aria-hidden="true" />
                        <span class="mt-2">{{ item.name }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <TransitionRoot as="template" :show="mobileMenuOpen">
            <Dialog as="div" class="relative z-20 md:hidden" @close="mobileMenuOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                        <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-indigo-700 pt-5 pb-4">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute top-1 right-0 -mr-14 p-1">
                                    <button type="button" class="flex h-12 w-12 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-white" @click="mobileMenuOpen = false">
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                        <span class="sr-only">Close sidebar</span>
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="mt-5 h-0 flex-1 overflow-y-auto px-2">
                                <nav class="flex h-full flex-col">
                                    <div class="space-y-1">
                                        <a v-for="item in sidebarNavigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white', 'group py-2 px-3 rounded-md flex items-center text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">
                                            <component :is="item.icon" :class="[item.current ? 'text-white' : 'text-indigo-300 group-hover:text-white', 'mr-3 h-6 w-6']" aria-hidden="true" />
                                            <span>{{ item.name }}</span>
                                        </a>
                                    </div>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Content area -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <header class="w-full">
                <div class="relative z-10 flex h-16 flex-shrink-0 border-b border-gray-200 bg-white shadow-sm">
                    <button type="button" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden" @click="mobileMenuOpen = true">
                        <span class="sr-only">Open sidebar</span>
                        <Bars3BottomLeftIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                    <div class="flex flex-1 justify-end px-4 sm:px-6">
                        <div class="ml-2 flex items-center space-x-4 sm:ml-6 sm:space-x-6">
                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative flex-shrink-0">
                                <div>
                                    <MenuButton class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full" :src='`https://dummyimage.com/200x200/000/fff&text=${initials}`' alt="" />
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                            <a :href="item.href" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">{{ item.name }}</a>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <div class="flex flex-1 items-stretch overflow-hidden mt-12">
                <main class="flex-1 overflow-y-auto">
                    <!-- Primary column -->
                    <section aria-labelledby="primary-heading" class="flex h-full min-w-0 flex-1 flex-col lg:order-last">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-xl font-semibold text-gray-900 mb-5">Hi {{ authUserName }} 👋</h1>
                                    <h1 class="text-xl font-semibold text-gray-900">Books</h1>
                                    <p>This is a sample page for demo purposes</p>
                                </div>
                            </div>
                            <div class="mt-8 flex flex-col">
                                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Image</th>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Title</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Availability</th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="book in books" :key="book.id">
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                                        <div class="flex items-center">
                                                            <div class="h-12 w-12 flex-shrink-0">
                                                                <img class="h-12 w-12 rounded" :src="book.image_url" alt="" />
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <div class="text-gray-900">{{ book.title }}</div>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <div class="text-gray-900">{{ book.price }}</div>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ book.availability }}</span>
                                                    </td>
                                                    <td class="relative whitespace-nowrap py-4 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <a href="#" class="text-indigo-600 hover:text-indigo-900"
                                                           @click="editModal(book)"
                                                        >Edit<span class="sr-only">, {{ book.name }}</span></a
                                                        >
                                                    </td>
                                                    <td class="relative whitespace-nowrap py-4 text-right text-sm font-medium sm:pr-6" @click="deleteBookPrompt(book)">
                                                        <a href="#" class="text-red-600 hover:text-indigo-900" >Delete<span class="sr-only">, {{ book.name }}</span></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="my-2 flex justify-end">
                                            <TailwindPagination
                                                :data="booksData"
                                                :limit="1"
                                                @pagination-change-page="getBooks"
                                            />
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div v-if="books.length === 0">
                                <NotFound />
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
        <EditBookModal
            v-if="loadEditModal"
            :activeBook="selectedBook"
            @selected="editItem"
            @close="toggleEditModal"
        />
    </div>
</template>
