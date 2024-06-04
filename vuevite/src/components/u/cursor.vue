<template>
    <!-- objek ini akan mengikuti kursor -->
    <div v-bind="$attrs" class="main" :class="{  'hovered': hovered }"
        :style="{ left: `${cursorX}px`, top: `${cursorY}px` }"></div>
</template>

<script>
    export default {
        inheritAttrs: false,
        // mounted() akan dijalankan setelah komponen ini di-mount ke DOM
        mounted() {
            // menambahkan event listener
            this.addEventListeners();
            // menambahkan event listener untuk mengikuti kursor
            this.mouseMove();
        },
        // updated() akan dijalankan setelah komponen ini di-update
        updated() {
            // menambahkan event listener
            this.removeEventListeners();
            this.addEventListeners();
        },
        methods: {
            // method untuk menambahkan event listener
            addEventListeners() {
                const state_1 = document.querySelectorAll(".mc");
                state_1.forEach((element) => {
                    element.removeEventListener("mouseenter", this.handleState1);
                    element.removeEventListener("mouseleave", this.handleState1);
                    element.addEventListener("mouseenter", this.handleState1.bind(this, true));
                    element.addEventListener("mouseleave", this.handleState1.bind(this, false)
                    );
                });
            },
            // method untuk menghapus event listener
            removeEventListeners() {
                const state_1 = document.querySelectorAll("button, .mc");
                state_1.forEach((element) => {
                    element.removeEventListener("mouseenter", this.handleState1);
                    element.removeEventListener("mouseleave", this.handleState1);
                });
            },
            mouseMove() {
                // update posisi objek mengikuti kursor, dan menghitung scroll.
                let lastKnownMousePosition = { x: 0, y: 0 };
                const updatePosition = () => {
                    requestAnimationFrame(() => {
                    this.cursorX = lastKnownMousePosition.x + window.scrollX;
                    this.cursorY = lastKnownMousePosition.y + window.scrollY;
                    });
                }
                document.addEventListener('mousemove', e => {
                    lastKnownMousePosition = { x: e.clientX, y: e.clientY };
                    updatePosition();
                });
                document.addEventListener('scroll', updatePosition);
            },
            // handler untuk mengubah state dan pos objek
            handleState1(mode) {
                this.hovered = mode;
            },
            handleMouseMove(event) {
                this.cursorX = event.pageX;
                this.cursorY = event.pageY;
            },

        },
        data() {
            // hovered untuk menentukan apakah objek sedang dihover atau tidak
            // cursorX dan cursorY untuk menentukan posisi objek
            return {
                hovered: false,
                cursorX: 0,
                cursorY: 0,
            };
        },
    };
</script>

<style>
    /* styling untuk objek yang akan mengikuti kursor */
    .main {
        position: absolute;
        transform: translate(-50%, -50%);
        pointer-events: none;
        overflow: hidden;
        z-index: 9999;
    }

    .main {
        width: 0px;
        height: 0px;
        background-color: rgba(255, 255, 255, 0);
        border: 2px solid rgba(0, 0, 0, 0);
        transition:
            background-color 0.5s ease-in-out,
            border 0.5s ease-in-out,
            left 0.2s ease-out,
            top 0.2s ease-out,
            width 0.2s,
            height 0.2s;
    }

    .hovered {
        width: 15px;
        height: 15px;
        background-color: rgba(255, 255, 255, 0.443);
        backdrop-filter: blur(10px);
        border: 1.5px solid rgba(0, 0, 0, 0.5);
    }
</style>
