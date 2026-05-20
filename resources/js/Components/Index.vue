<script lang="ts">
export const description = "An inset sidebar with dynamic breadcrumbs.";
export const iframeHeight = "800px";
</script>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

import AppSidebar from "@/Components/Appsidebar.vue";
import NavUser from "./NavUser.vue";

import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";

import { Separator } from "@/components/ui/separator";

import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";

/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

type BreadcrumbType = {
    name: string;
    href?: string;
};

type User = {
    name: string;
    email: string;
    avatar: string;
};

/*
|--------------------------------------------------------------------------
| User (top right)
|--------------------------------------------------------------------------
*/

const user: User = {
    name: "Dr. Jay Estoquia",
    email: "Pavilion 2",
    avatar: "/avatars/shadcn.jpg",
};

/*
|--------------------------------------------------------------------------
| Get breadcrumbs from Laravel (Inertia)
|--------------------------------------------------------------------------
*/

const page = usePage();

const breadcrumbs = computed(() => {
    return (page.props.breadcrumbs ?? []) as BreadcrumbType[];
});
</script>

<template>
    <SidebarProvider>
        <AppSidebar />

        <SidebarInset>
            <header
                class="flex w-full justify-between h-16 shrink-0 items-center gap-2 drop-shadow bg-primary-foreground"
            >
                <div class="flex justify-between items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />

                    <Separator
                        orientation="vertical"
                        class="mr-2 data-[orientation=vertical]:h-4"
                    />

                    <!-- Dynamic Breadcrumb -->
                    <Breadcrumb>
                        <BreadcrumbList>
                            <template
                                v-for="(crumb, index) in breadcrumbs"
                                :key="index"
                            >
                                <BreadcrumbItem class="hidden md:block">
                                    <BreadcrumbLink
                                        v-if="crumb.href"
                                        :href="crumb.href"
                                    >
                                        {{ crumb.name }}
                                    </BreadcrumbLink>

                                    <BreadcrumbPage v-else>
                                        {{ crumb.name }}
                                    </BreadcrumbPage>
                                </BreadcrumbItem>

                                <BreadcrumbSeparator
                                    v-if="index < breadcrumbs.length - 1"
                                    class="hidden md:block"
                                />
                            </template>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>

                <div>
                    <NavUser :user="user" class="px-4" />
                </div>
            </header>

            <slot />
        </SidebarInset>
    </SidebarProvider>
</template>
