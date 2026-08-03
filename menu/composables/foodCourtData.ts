import {getCmsBaseUrl} from "~/utils/cmsBaseUrl";

export type IMenuData__foodCourt = IMenuData__foodCourt__weekMenu[]

export interface IMenuData__foodCourt__weekMenu {
  jour1_station1_prix_public: number,
  jour1_station2_prix_public: number
  jour1_station3_prix_public: number
  jour1_station3_prix_apprenti: number
  jour1_station4_prix_public: number

  jour2_station1_prix_public: number
  jour2_station2_prix_public: number
  jour2_station3_prix_public: number
  jour2_station3_prix_apprenti: number
  jour2_station4_prix_public: number

  jour3_station1_prix_public: number
  jour3_station2_prix_public: number
  jour3_station3_prix_public: number
  jour3_station3_prix_apprenti: number
  jour3_station4_prix_public: number

  jour4_station1_prix_public: number
  jour4_station2_prix_public: number
  jour4_station3_prix_public: number
  jour4_station3_prix_apprenti: number
  jour4_station4_prix_public: number

  jour5_station1_prix_public: number
  jour5_station2_prix_public: number
  jour5_station3_prix_public: number
  jour5_station3_prix_apprenti: number
  jour5_station4_prix_public: number

  id: string

  date: string

  station1_name: string
  station2_name: string
  station3_name: string
  station4_name: string

  jour1_station1_menu: string
  jour1_station1_description: string
  jour1_station1_prix_apprenti: string
  jour1_station2_menu: string
  jour1_station2_description: string
  jour1_station2_prix_apprenti: string
  jour1_station3_menu: string
  jour1_station3_description: string
  jour1_station4_menu: string
  jour1_station4_description: string
  jour1_station4_prix_apprenti: string

  jour2_station1_menu: string
  jour2_station1_description: string
  jour2_station1_prix_apprenti: string
  jour2_station2_menu: string
  jour2_station2_description: string
  jour2_station2_prix_apprenti: string
  jour2_station3_menu: string
  jour2_station3_description: string
  jour2_station4_menu: string
  jour2_station4_description: string
  jour2_station4_prix_apprenti: string

  jour3_station1_menu: string
  jour3_station1_description: string
  jour3_station1_prix_apprenti: string
  jour3_station2_menu: string
  jour3_station2_description: string
  jour3_station2_prix_apprenti: string
  jour3_station3_menu: string
  jour3_station3_description: string
  jour3_station4_menu: string
  jour3_station4_description: string
  jour3_station4_prix_apprenti: string

  jour4_station1_menu: string
  jour4_station1_description: string
  jour4_station1_prix_apprenti: string
  jour4_station2_menu: string
  jour4_station2_description: string
  jour4_station2_prix_apprenti: string
  jour4_station3_menu: string
  jour4_station3_description: string
  jour4_station4_menu: string
  jour4_station4_description: string
  jour4_station4_prix_apprenti: string

  jour5_station1_menu: string
  jour5_station1_description: string
  jour5_station1_prix_apprenti: string
  jour5_station2_menu: string
  jour5_station2_description: string
  jour5_station2_prix_apprenti: string
  jour5_station3_menu: string
  jour5_station3_description: string
  jour5_station4_menu: string
  jour5_station4_description: string
  jour5_station4_prix_apprenti: string
}

export async function getFoodCourtData(): Promise<IMenuData__foodCourt> {
  const data = await fetch(`${getCmsBaseUrl()}/foodcourt`, {
    method: 'GET',
  })

  return data.json()
}

export function foodCourt_GetCurrentWeekMenu(menus: IMenuData__foodCourt): IMenuData__foodCourt__weekMenu | null {
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  const currentWeekMenu = menus
    .filter(menu => new Date(menu.date) <= today)
    .sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
    [0] || null

  return currentWeekMenu
}
