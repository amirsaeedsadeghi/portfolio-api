import { config } from "../../config";

export async function getNavbarItems({ signal } = {}) {

  const response = await fetch(`${config.apiBaseUrl}/navbar-items`, { signal });

  if (!response.ok) {
    let message = `Failed to fetch Navbar Items (status ${response.status})`;
    try {
      const result = await response.json();
      if (result?.message) message = result.message;
    } catch {
      console.error("Error parsing error response");
    }
    throw new Response(message, { status: response.status });
  }
  const { data } = await response.json();
  return data;
}