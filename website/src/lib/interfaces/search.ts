export type SearchGroup = 'all' | 'pages' | 'events' | 'projects';

export interface SearchCover {
	readonly url: string;
	readonly alt: string | null;
}

export interface SearchResult {
	readonly id: string;
	readonly title: string;
	readonly url: string;
	readonly type: string;
	readonly typeLabel: string;
	readonly group: Exclude<SearchGroup, 'all'>;
	readonly excerpt: string;
	readonly cover: SearchCover | null;
}

export interface SearchResponse {
	readonly query: string;
	readonly group: SearchGroup;
	readonly offset: number;
	readonly counts: Record<SearchGroup, number>;
	readonly total: number;
	readonly hasMore: boolean;
	readonly results: SearchResult[];
}
