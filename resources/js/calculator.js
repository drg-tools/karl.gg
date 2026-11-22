import { createApp } from "vue";
import LoadoutCalculator from "./components/LoadoutCalculator.vue";

const element = document.getElementById("loadout-calculator");
if (element) {
    const config = JSON.parse(
        document.getElementById("calculator-data").textContent
    );
    createApp(LoadoutCalculator, { config }).mount(element);
}
