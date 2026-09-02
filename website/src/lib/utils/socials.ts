import type { Component } from 'svelte';
import type { SocialPlatform } from '$lib/interfaces/global';
import IconFacebook from '$lib/components/svg/IconFacebook.svelte';
import IconInstagram from '$lib/components/svg/IconInstagram.svelte';
import IconLinkedin from '$lib/components/svg/IconLinkedin.svelte';
import IconYoutube from '$lib/components/svg/IconYoutube.svelte';
import IconTiktok from '$lib/components/svg/IconTiktok.svelte';
import IconSnapchat from '$lib/components/svg/IconSnapchat.svelte';
import IconX from '$lib/components/svg/IconX.svelte';

/** Icon per social platform. The label below is the accessible name that goes with it. */
export const socialIcons: Record<SocialPlatform, Component<{ class?: string }>> = {
	facebook: IconFacebook,
	instagram: IconInstagram,
	linkedin: IconLinkedin,
	youtube: IconYoutube,
	tiktok: IconTiktok,
	snapchat: IconSnapchat,
	x: IconX
};

export const socialLabels: Record<SocialPlatform, string> = {
	facebook: 'Facebook',
	instagram: 'Instagram',
	linkedin: 'LinkedIn',
	youtube: 'YouTube',
	tiktok: 'TikTok',
	snapchat: 'Snapchat',
	x: 'X'
};
