import { config } from "../../config";

export async function getProjects({ signal } = {}) {

  const response = await fetch(`${config.apiBaseUrl}/projects?includes=stacks,images&sort=order`, { signal });
  if (!response.ok) {
    let message = `Failed to fetch Projects (status ${response.status})`;
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

export async function getProjectById(id, { signal } = {}) {
  const response = await fetch(`${config.apiBaseUrl}/projects/${id}?includes=stacks,images`, { signal });
  if (!response.ok) {
    let message = `Failed to fetch Project (status ${response.status})`;
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

export async function getProjectBySlug(slug, { signal } = {}) {
  const response = await fetch(`${config.apiBaseUrl}/projects/${slug}?includes=images`, { signal });
  if (!response.ok) {
    let message = `Failed to fetch Project (status ${response.status})`;
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