export type CommentUser = {
  id: number;
  name: string;
};

export type ReportComment = {
  id: number;
  report_id: number;
  user_id: number;
  body: string;
  created_at: string;
  user: CommentUser;
  canDelete: boolean;
};
