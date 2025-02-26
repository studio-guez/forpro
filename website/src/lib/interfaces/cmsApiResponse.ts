/**
 * site info
 */

export interface ISiteInfo {
    "title": string
    "nav": ISiteDataNavItem[]
    footer: string
}

export interface ISiteDataNavItem {
    "title": string | null
    "heroTitle": "string" | null
    "showmenu": boolean | null
    "slug": string | null
    "url": string | null
    "uri": string | null
    "hero": {
        "text": string | null
        "backgroundcolor": string | null
        "textcolor": string | null
    } | null
}

/**
 * PAGE
 */
export interface IPage {
    options: IOptions
    body: IBody
    seo: ISeo
}

export interface IPageEvents extends IPage {
    childrenDetails: IChildrenDetils__event[]
}

export interface IPage_Event extends IPage {
    pageInfo: {
        title: string,
        cover: string,
        target: string,
        category: string,
        suboptions: string,
        place: string,
        googlemapaddress: string,
        description: string,
        datestart: string,
        hourstart: string,
        dateend: string,
        hourend: string,
        body: string,
        uuid: string
    }
}

export interface IOptions {
    showMenu: boolean
    showNewsletter: boolean
    hero: {
        text: string
        backgroundcolor: string
        textcolor: string
    }
}

export interface IChildrenDetils {
    cover: IImage[],
    pageContent: {
        content: {
            title: string,
            cover: string,
            target: string,
            category: string,
            place: string,
            googlemapaddress: string,
            description: string,
            datestart: string,
            hourstart: string,
            dateend: string,
            hourend: string,
            body: string,
            uuid: string,
            dates: string
        },
        translations: [],
        children: [],
        files: string[],
        id: string,
        mediaUrl: string,
        mediaRoot: string,
        num: number,
        parent: string,
        slug: string,
        template: {},
        uid: string,
        uri: string,
        url: string
    }
}

export interface IChildrenDetils__event extends IChildrenDetils {
    pageContent: {
        content: {
            isarchive: "true" | "false",
            title: string,
            cover: string,
            target: string,
            category: string,
            place: string,
            googlemapaddress: string,
            description: string,
            datestart: string,
            hourstart: string,
            dateend: string,
            hourend: string,
            body: string,
            uuid: string,
            dates: string
        }
        translations: [],
        children: [],
        files: string[],
        id: string,
        mediaUrl: string,
        mediaRoot: string,
        num: number,
        parent: string,
        slug: string,
        template: {},
        uid: string,
        uri: string,
        url: string
    }
}

export interface IBody {
    [key: string]: {
        image: IImage[]
        content: ICta
            | IQuote
            | ICapsules
            | ICards
            | IProfiles
            | IList
            | IDropdown
            | ICardsFocus
            | IHtmlContent
            | IBlockMap
            | IAnimatedList
            | IGoogleMaps
            | IBlockImage
            | IBlockListLogo
            | IBlockVideo
            | IBlockTimeline
            | IBlockLinkListContent
            | IBlockLinkAgenda
    }
}


export interface IBlock {
    id: string
    isHidden: boolean
    type: 'cta'
        | 'quote'
        | 'capsules'
        | 'cards'
        | 'profiles'
        | 'list'
        | 'dropdown'
        | 'cards-focus'
        | 'body'
        | 'map'
        | 'animated-list'
        | 'google-maps'
        | 'image'
        | 'logos-list'
        | 'video'
        | 'timeline'
        | 'listOfPDF'
        | 'agenda'
}

export interface ISeo {
    metaTemplate: string
    metaDescription: string
    metaAuthor: string
    metaImage: string
    metaPhoneNumber: string
    ogTemplate: string
    ogDescription: string
    ogImage: string
    ogSiteName: string
    twitterTemplate: string
    twitterDescription: string
    twitterImage: string
    twitterCardType: string
    twitterSite: string
    twitterCreator: string
}




/**
 * BLOCK IMAGE
 */
export interface IImage {
    caption: string | null
    alt: string | null
    link: string | null
    photoCredit: string | null
    url: string
    mediaUrl: string
    width: number
    height: number
    resize: {
        tiny: string
        small: string
        reg: string
        large: string
        xxl: string
    }
}





/**
 * CTA
 */
export interface ICta extends IBlock {
    type: 'cta'
    content: {
        image: string[]
        text: string
        link: string
        backgroundcolor: string
        textcolor: string
        styles: 'style1' | 'style2'
        target_blank: 'true' | 'false'
    }
}





/**
 * QUOTE
 */
export interface IQuote extends IBlock {
    content: {
        text: string
    }
    type: 'quote'
}





/**
 * CAPSULE
 */
export interface ICapsules extends IBlock {
    content: {
        capsules: ICapsule[]
    }
    type: 'capsules'
}

export interface ICapsule {
    title: string
    text: string
    image: string[]
    style: 'style1' | 'style2' | 'style3' | 'style4'
}


/**
 * VIDEO
 */
export interface IBlockVideo extends IBlock {
    "content": {
        "location": "web",
        "url": string,
        "video": [],
        "poster": [],
        "autoplay": "false",
        "muted": "true",
        "loop": "false",
        "controls": "true",
        "preload": "auto",
        is_vertical: 'true' | 'false'
    }
    "type": "video"
    "id": string,
    "isHidden": boolean,
}

export interface ICapsule {
    title: string
    text: string
    image: string[]
    style: 'style1' | 'style2' | 'style3' | 'style4'
}





/**
 * CARD
 */
export interface ICards extends IBlock {
    content: {
        style: 'style1' | 'style2',
        cards: ICard[]
    },
    type: 'cards'
}

export type ApiCardThemeColor =
    "#1754ff"
    | "#3df069"
    | "#b9e6ff"
    | "#bea5e6"
    | "#ff00fc"

export interface ICard {
    title: "",
    image: IImage[],
    link: "",
    text: ""
    imageData: IImage[] | undefined
    "color"?: ApiCardThemeColor
}





/**
 * PROFILE
 */
export interface IProfiles extends IBlock {
    content: {
        title: string
        style: 'style1' | 'style2';
        profiles: IProfile[];
    },
    type: 'profiles'
}


export interface IProfile {
    title: string;
    subtitle: string;
    description: string;
    images: string[];
    lien: string;
    mailto: string;
    imageData: IImage[] | undefined
}


/**
 * BLOCK LIST
 */

export interface IList extends IBlock {
    content: {
        list: ListItem[];
    }
    type: 'list'
}

export interface ListItem {
    key: string;
    value: string;
}





/**
 * BLOCK DROPDOWN
 */
export interface IDropdown extends IBlock {
    content: {
        dropdown: IDropdownItem[];
    }
    type: 'dropdown'
}

export interface IDropdownItem {
    title: string;
    content: string;
    link: string;
}






/**
 * BLOCK CARDSFOCUS
 * */
export interface ICardsFocus extends IBlock {
    "content": {
        "cards": ICardFocusItem[]
    },
    type: 'cards-focus'
}

export interface ICardFocusItem {
    "style": 'entreprises' | 'entourage' | 'jeunes'
    "title": string,
    "subtitle": string,
    "link": string
}

/**
 * BODY
 */
export interface IHtmlContent extends IBlock {
    "content": {
        "text": string
    }
    type: 'body'
}


/**
 * Map
 */
export interface IBlockMap extends IBlock {
    "content": {
        "style": "style1" | "style2" | "style3"
    },
    type: 'map'
}

/**
 *
 */
export type AnimatedListStyle = 'entreprises' | 'entourage' | 'jeunes' | 'explore' | 'leLab'

export interface IAnimatedList extends IBlock {
    "content": {
        "style": AnimatedListStyle
    },
    type: 'animated-list'
}

/**
 * google maps
 */
export interface IGoogleMaps extends IBlock {
    "content": {
        link: string
    },
    type: 'google-maps'
}


/**
 * image
 */
export interface IBlockImage extends IBlock {
    "content": {
        link: string
        alt: string,
        fixed: "true" | "false"
    },
    type: 'image'
}


/**
 * list logo
 */

export interface IBlockListLogo extends IBlock {
    "image": IImage[],
    "content": {
        "content": {
            "image": string[]
        },
        "id": "2e3f72cf-be8e-4882-9c19-2b69c5213b34",
        "isHidden": false,
    }
    "type": "logos-list"
}

/**
 * timeline en speed
 * */


export interface IBlockTimeline extends IBlock {
    "content": {
        items: IBlockTimeline_item[]
    }
    type: 'timeline'
}

export interface IBlockTimeline_item {
    title: string,
    date: string,
    dateend: string,
    datemessage: string,
    details: string,
    content: string,
    button_text: string,
    button_link: string
}

/**
 * block link list
 */

export interface IBlockLinkList extends IBlock {
    "image": [],
    "content": IBlockLinkListContent
}

export interface IBlockLinkListContent {
    "content": {
        "title": string,
        "listoflinks": {
            "color": string,
            "title": string,
            "lien": string
        }[]
    },
    "id": string,
    "isHidden": boolean,
    "type": "listOfPDF"
}

/**
 * block agenda
 * */

export interface IBlockLinkAgenda extends IBlock {
    "image": [],
    "type": "agenda"
}
