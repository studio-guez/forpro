export function formatPrice(price?: number | string): string | null {

  if( !price) return null
  price = Number(price)

  if (Number.isInteger(price)) {
    return `${price}.–`;
  } else {
    return price.toFixed(2);
  }
}
