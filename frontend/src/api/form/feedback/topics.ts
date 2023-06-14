import { requestPromise } from '@/utils/request'
import { IFeedbackTopic } from '@/types'

/**
 * Получение списка тем для формы "Обсудить задачу"
 * @returns
 */
export default async () => {
  return await requestPromise<FeedbackTopicsResponse>({
    url: 'forms/discuss_task_list/',
    method: 'get'
  })
}

export interface FeedbackTopicsResponse {
  interest: IFeedbackTopic[]
  type: IFeedbackTopic[]
}
