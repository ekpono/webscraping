<template>
    <!--
      This example requires updating your template:

      ```
      <html class="h-full">
      <body class="h-full">
      ```
    -->
    <div class="flex min-h-full flex-col bg-white pt-16 pb-12">
        <main class="mx-auto flex w-full max-w-7xl flex-grow flex-col justify-center px-6 lg:px-8">
            <div class="flex flex-shrink-0 justify-center">
                <a href="/" class="inline-flex">
                    <span class="sr-only">Book Store</span>
                </a>
            </div>
            <div class="py-16">
                <div class="text-center">
                    <p class="text-base font-semibold text-indigo-600">404</p>
                    <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Page not found.</h1>
                    <p class="mt-2 text-base text-gray-500">Sorry, we couldn’t find the page you’re looking for.</p>
                    <div class="mt-6">
                        <a href="/dashboard" class="text-base font-medium text-indigo-600 hover:text-indigo-500">
                            Go back home
                            <span aria-hidden="true"> &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: "NotFound"
}
</script>

<style scoped>

</style>
