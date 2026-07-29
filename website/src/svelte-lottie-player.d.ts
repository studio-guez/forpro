declare module '@lottiefiles/svelte-lottie-player' {
    import { SvelteComponent } from 'svelte';

    export interface LottiePlayerProps {
        autoplay?: boolean
        background?: string
        controls?: boolean
        controlsLayout?: string[] | string
        count?: number
        defaultFrame?: number
        direction?: number | string
        height?: number | string
        hover?: boolean
        loop?: boolean
        mode?: 'normal' | 'bounce'
        onToggleZoom?: (isZoomed: boolean) => void
        renderer?: 'svg' | 'canvas'
        speed?: number | string
        src?: string
        style?: string
        width?: number | string
    }

    export class LottiePlayer extends SvelteComponent<LottiePlayerProps> {

        /**
         * Returns the lottie-web version and this player's version
         */
        getVersions(): { lottieWebVersion: string, svelteLottiePlayerVersion: string };
        /**
         * Returns the lottie-web instance used in the component.
         */
        getLottie(): never;
        /**
         * Pause animation play.
         */
        pause(): void;
        /**
         * Start playing animation.
         */
        play(): void;
        /**
         * Stops animation play.
         */
        stop(): void;
        /**
         * Freeze animation play.
         * This internal state pauses animation and is used to differentiate between
         * user requested pauses and component instigated pauses.
         */
        freeze(): void;
        /**
         * Resize animation.
         */
        resize(): void;
        /**
         * Seek to a given frame.
         * @param frame Frame number or Percent string to seek to.
         */
        seek(frame: number|string): void;
        /**
         * Snapshot the current frame as SVG.
         * @param download If 'download' argument is boolean true, then a download is triggered in browser.
         */
        snapshot(download?: boolean): void;
        /**
         * Sets the looping of the animation.
         * @param value Whether to enable looping. Boolean true enables looping.
         */
        setLooping(value: boolean): void;
        /**
         * Sets the speed of the animation.
         * @param value The speed of the animation. 1 is normal speed.
         */
        setSpeed(value: number): void;
        /**
         * Animation play direction.
         * @param value Direction values.
         */
        setDirection(value: number): void;
        /**
         * Toggle playing state.
         */
        togglePlay(): void;
        /**
         * Toggle zoom state.
         */
        toggleZoom(): void;
        /**
         * Toggles animation looping.
         */
        toggleLooping(): void;
        /**
         * Sets background color.
         */
        setBackgroundColor(color: string): void;


    }
}
