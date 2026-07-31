import {getCmsBaseUrl} from "~/utils/cmsBaseUrl";

export type IMenuData__foodLab = {
  menus: IMenuData__foodLab__weekMenu[],
  footer: string,
}

export interface IMenuData__foodLab__weekMenu {
  id: string,
  date: string,

  prix: 21,

  jour1_menu: string,
  jour2_menu: string,
  jour3_menu: string,
  jour4_menu: string,
  jour5_menu: string,
  jour6_menu: string
}

export async function getfoodLabData(): Promise<IMenuData__foodLab> {
  const data = await fetch(`${getCmsBaseUrl()}/foodlab`, {
    method: 'GET',
  })

  return data.json()
}

export function foodLab_GetCurrentWeekMenu(menus: IMenuData__foodLab): IMenuData__foodLab__weekMenu | null {
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  const currentWeekMenu = menus.menus
    .filter(menu => new Date(menu.date) <= today)
    .sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
    [0] || null

  console.info(currentWeekMenu)

  return currentWeekMenu
}
