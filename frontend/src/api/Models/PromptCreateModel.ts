export interface PromptCreateModel {
    scope: number;
    name: string;
    systemMessage: string;
    userMessage: string;
    expectedResult: string;
}