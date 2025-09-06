import { config } from "../../config";


export async function getAboutMe({ signal } = {}) {

  const response = await fetch(`${config.apiBaseUrl}/about-me`, { signal });

  if (!response.ok) {
    let message = `Failed to fetch AboutMe (status ${response.status})`;
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
