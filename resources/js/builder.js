import { createApp } from "vue";
import LoadoutBuilder from "./components/LoadoutBuilder.vue";

const element = document.getElementById("loadout-builder");
if (element) {
    const config = JSON.parse(document.getElementById("builder-data").textContent);
    createApp(LoadoutBuilder, { config }).mount(element);
}
