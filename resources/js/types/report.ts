export type ReportType = 'lost' | 'found';

export type ReportStatus = 'active' | 'resolved';

export type ReportFilters = {
  q: string;
  type: ReportType | '';
  category: string;
};

export type ReportUser = {
  id: number;
  name: string;
};

export type Report = {
  id: number;
  user_id: number;
  type: ReportType;
  title: string;
  category: string;
  description: string;
  color: string | null;
  location: string;
  event_date: string;
  image_path: string | null;
  image_url: string | null;
  status: ReportStatus;
  is_hidden: boolean;
  created_at: string;
  updated_at: string;
  user?: ReportUser;
};

export type Pagination = {
  current_page: number;
  last_page: number;
  next_page_url: string | null;
  prev_page_url: string | null;
  total: number;
  data: Report[];
};
