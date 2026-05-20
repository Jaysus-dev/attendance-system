<script setup lang="ts">
import type { SidebarProps } from "@/Components/ui/sidebar";

import {
    FileUser,
    AudioWaveform,
    BookOpen,
    Bot,
    Command,
    Frame,
    GalleryVerticalEnd,
    Map,
    PieChart,
    Settings2,
    SquareTerminal,
    SquareUser,
    BookSearch,
    Book,
    UserRoundCheck,
    MessageSquareWarning,
    Users,
    Settings,
    CalendarCheck,
} from "lucide-vue-next";
import NavMain from "@/Components/NavMain.vue";
import NavProjects from "@/Components/NavProjects.vue";
import NavUser from "@/Components/NavUser.vue";
import TeamSwitcher from "@/Components/TeamSwitcher.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/Components/ui/sidebar";

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: "icon",
});

// This is sample data.
const data = {
    user: {
        name: "shadcn",
        email: "m@example.com",
        avatar: "/avatars/shadcn.jpg",
    },
    teams: [
        {
            name: "ATTENDANCE",
            logo: CalendarCheck,
            plan: "Management System",
        },
    ],
    navMain: [
        {
            title: "Dashboard",
            url: route("dashboard"),
            icon: PieChart,
            isActive: route().current("dashboard"),
        },
        {
            title: "Students",
            url: route("students"),
            icon: FileUser,
            isActive: route().current("students"),
            //collapsible: true,
            //isActive: true,
        },
        {
            title: "Teachers",
            url: route("teachers"),
            icon: SquareUser,
            isActive: route().current("teachers"),
        },
        {
            title: "Classes",
            url: route("classes"),
            icon: BookSearch,
            isActive: route().current("classes"),
        },
        {
            title: "Subjects",
            url: route("subjects"),
            icon: Book,
            isActive: route().current("subjects"),
        },
    ],
    projects: [
        {
            name: "Attendance",
            url: route("attendance"),
            icon: UserRoundCheck,
            isActive: route().current("attendance"),
        },
        {
            name: "Users",
            url: route("users"),
            icon: Users,
            isActive: route().current("users"),
        },
        {
            name: "Reports",
            url: route("reports"),
            icon: MessageSquareWarning,
            isActive: route().current("reports"),
        },
        {
            name: "Settings",
            url: route("settings"),
            icon: Settings,
            isActive: route().current("settings"),
        },
    ],
};
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <TeamSwitcher :teams="data.teams" />
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="data.navMain" />
            <NavProjects :projects="data.projects" />
        </SidebarContent>
        <SidebarFooter> <!-- <NavUser :user="data.user" /> --> </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
