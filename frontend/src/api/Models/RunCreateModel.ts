import { ModelRunModel } from "./ModelRunModel";

export interface RunCreateModel {
  promptId?: number;
  models: ModelRunModel[];
}
