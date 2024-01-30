/**
 * PAGE
 */
export interface IPage {
    options: IOptions
    body: IBody
    seo: ISeo
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

export interface IBody {
    [key: string]: {
        image: IImage[]
        content: ICta | IQuote | ICapsules | ICards | IProfiles | IList | IDropdown | ICardsFocus | IHtmlContent
    }
}


export interface IBlock {
    id: string
    isHidden: boolean
    type: 'cta' | 'quote' | 'capsules' | 'cards' | 'profiles' | 'list' | 'dropdown' | 'cards-focus' | 'body' | 'map'
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
        style: 'style1' | 'style2'
        capsules: ICapsule[]
    }
    type: 'capsules'
}

export interface ICapsule {
    title: string
    text: string
    image: string[]
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

export interface ICard {
    title: "",
    image: IImage[],
    link: "",
    text: ""
}





/**
 * PROFILE
 */
export interface IProfiles extends IBlock {
    content: {
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
        "text": "<p>bonjour le text</p><p>bonjour me monde</p>"
    }
    type: 'body'
}


/**
 * Map
 */
export interface IHtmlContent extends IBlock {
    "content": {
        "style": "style1" | "style2"
    },
    type: 'map'
}
