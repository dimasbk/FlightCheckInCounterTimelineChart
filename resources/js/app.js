import "./bootstrap";

import {
    SvelteGantt,
    SvelteGanttTable,
    MomentSvelteGanttDateAdapter,
} from "svelte-gantt";
import { onMount } from "svelte";
import { time } from "../utils";
import moment from "moment";
import GanttOptions from "../components/GanttOptions.svelte";
