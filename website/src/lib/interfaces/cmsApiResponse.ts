export interface IOptions {
    showMenu: boolean
    showNewsletter: boolean
    hero: {
        text: string
        backgroundcolor: string
        textcolor: string
    }
}

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

export interface IContent {
    image: string[]
    text: string
    link: string
    backgroundcolor: string
    textcolor: string
    styles: string
}

export interface ICta {
    content: IContent
    id: string
    isHidden: boolean
    type: 'cta'
}

export interface IQuote {
    content: {
        text: string
    }
    id: string
    isHidden: boolean
    type: 'quote'
}

export interface ICapsule {
    title: string
    text: string
    image: string[]
}

export interface ICapsules {
    content: {
        style: string
        capsules: ICapsule[]
    }
    id: string
    isHidden: boolean
    type: 'capsules'
}

export interface IBody {
    [key: string]: {
        image: IImage[]
        content: ICta | IQuote | ICapsules
    }
}

export interface IPage {
    options: IOptions
    body: IBody
}
